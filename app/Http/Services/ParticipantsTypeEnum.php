<?php
namespace App\Http\Services;

// This enum is a homologation from the "nov_novedades_participantes" heidy-table
enum ParticipantsTypeEnum: string
{
    case TITULAR = "TIT";
    case DERECHO_HABIENTE = "DH";
    case SOLICITANTE = "SOL";
    case APODERADO = "APOD";
    case INFORMADOR = "INF";
}

