<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Models\Reservation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservationController {
    public function store(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $reserva = Reservation::create($data);
        return ResponseBuilder::respondWithData($response, HttpStatus::Created, $reserva);
    }

    public function index(Request $request, Response $response): Response {
        $reservas = Reservation::with('local','unity')->get();
        return ResponseBuilder::respondWithData($response, data: $reservas);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $reserva = Reservation::find($args['id']);

        if (!$reserva) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Reserva não encontrada');
        }

        $reserva->update($data);
        return ResponseBuilder::respondWithData($response, HttpStatus::OK, $reserva);
    }

    public function find(Request $request, Response $response, array $args): Response {
        $reserva = Reservation::with('local','unity')->find($args['id']);

        if (!$reserva) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Reserva não encontrada');
        }

        return ResponseBuilder::respondWithData($response, data: $reserva);
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $reserva = Reservation::find($args['id']);

        if (!$reserva) {
            return ResponseBuilder::respondWithError($response, HttpStatus::NotFound, 'Reserva não encontrada');
        }

        $reserva->delete();
        return ResponseBuilder::respondWithData($response, HttpStatus::OK);
    }
}
