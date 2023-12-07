<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;

use App\Models\Contact;

use App\Core\Controller;
use App\Core\Validation;

use App\Actions\Contact\AddContactAction;
use App\Actions\Contact\UpdateContactAction;

use App\Middlewares\VerifyTokenMiddleware;

class ContactsController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(
            [
                "class" => VerifyTokenMiddleware::class,
                "actions" => ["index", "show", "store", "update", "delete"]
            ]
        );
    }

    public function index(Request $request, Response $response)
    {
        $contacts = Contact::find(['user_id' => $request->user()->id], 'favorite DESC, name ASC');
        return $response->jsonResponse([
            'success' => true,
            'contacts' => $contacts
        ], 200);
    }

    public function show(Request $request, Response $response)
    {
        $contact = Contact::find(['id' => $request->params['id'], 'user_id' => $request->user()->id]);

        if (!$contact) {
            return $response->jsonResponse([
                'success' => false,
                'message' => 'Le contact n\'existe pas.'
            ], 404);
        }

        return $response->jsonResponse([
            'success' => true,
            'contact' => $contact
        ], 200);
    }

    public function store(Request $request, Response $response)
    {
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'phone' => 'required|phone',
            'category' => 'required',
            'favorite' => 'required',
        ];

        $errors = Validation::validate($request, $rules, Contact::class);

        if (empty($errors)) {
            if (($callback = (new AddContactAction())->execute(
                $request->body['name'],
                $request->body['surname'],
                $request->body['email'],
                $request->body['phone'],
                $request->body['category'],
                $request->body['image'],
                $request->body['favorite'],
                $request->user()->id
            ))) {
                if (!isset($callback['errors'])) {
                    return $response->jsonResponse([
                        'success' => true,
                        'success' => $callback,
                    ], 200);
                } else {
                    return $response->jsonResponse([
                        'success' => false,
                        'errors' => $callback['errors']
                    ], 422);
                }
            }
        } else {
            return $response->jsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }
    }

    public function update(Request $request, Response $response)
    {
        if (!Contact::find(['id' => $request->params['id'], 'user_id' => $request->user()->id])) {
            return $response->jsonResponse([
                'success' => false,
                'message' => 'Le contact n\'existe pas.'
            ], 404);
        }

        $errors = Validation::validate($request, [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'phone' => 'required|phone',
            'category' => 'required',
            'favorite' => 'required'
        ], Contact::class);

        if (empty($errors)) {
            (new UpdateContactAction())->execute(
                $request->body['name'],
                $request->body['surname'],
                $request->body['email'],
                $request->body['phone'],
                $request->body['category'],
                $request->body['favorite'],
                $request->body['image'],
                $request->user()->id,
                $request->params['id']
            );
            return $response->jsonResponse([
                'success' => true,
                'message' => 'Le contact a bien été modifié.'
            ], 200);
        } else {
            return $response->jsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }
    }

    public function delete(Request $request, Response $response)
    {
        if (!Contact::find(['id' => $request->params['id'], 'user_id' => $request->user()->id])) {
            return $response->jsonResponse([
                'success' => false,
                'message' => 'Le contact n\'existe pas.'
            ], 404);
        }

        Contact::delete(['id' => $request->params['id'], 'user_id' => $request->user()->id]);

        return $response->jsonResponse([
            'success' => true,
            'message' => 'Le contact a bien été supprimé.'
        ], 200);
    }
}
