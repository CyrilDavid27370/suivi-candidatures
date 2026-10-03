<?php

namespace App\Enum;

enum StatusCandidature: string
{
    case A_ENVOYER = 'À envoyer';
    case ENVOYEE = 'Envoyée';
    case RELANCEE = 'Relancée';
    case ENTRETIEN = 'Entretien';
    case ACCEPTEE = 'Acceptée';
    case REFUSEE = 'Refusée';

    public function label(): string
    {
        return match ($this) {
            self::A_ENVOYER => 'À envoyer',
            self::ENVOYEE => 'Envoyée',
            self::RELANCEE => 'Relancée',
            self::ENTRETIEN => 'Entretien',
            self::ACCEPTEE => 'Acceptée',
            self::REFUSEE => 'Refusée',
        };
    }
}
