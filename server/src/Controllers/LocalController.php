<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Models\Local;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LocalController {
    public function index(Request $request, Response $response): Response 
    {
        $locais = Local::all();
        return ResponseBuilder::respondWithData($response, data: $locais);
    }

    public function store(Request $request, Response $response): Response 
    {
        $data = $request->getParsedBody();
        $local = Local::create($data);
        return ResponseBuilder::respondWithData($response, HttpStatus::Created, $local);
    }

    public function show(Request $request, Response $response, array $args): Response 
    {
        $local = Local::find($args['id']);
        if (!$local) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Local não encontrado');
        }
        return ResponseBuilder::respondWithData($response, data: $local);
    }

    public function update(Request $request, Response $response, array $args): Response 
    {
        $data = $request->getParsedBody();
        $local = Local::find($args['id']);
        if (!$local) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Local não encontrado');
        }
        $local->update($data);
        return ResponseBuilder::respondWithData($response, data: $local);
    }

    public function delete(Request $request, Response $response, array $args): Response 
    {
        $local = Local::find($args['id']);
        if (!$local) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Local não encontrado');
        }
        $local->delete();
        return ResponseBuilder::respondWithData($response, HttpStatus::NoContent);
    }
}
