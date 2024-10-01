<?php
/**
 * User: Zura
 * Date: 12/2/2023
 * Time: 6:34 PM
 */
namespace App\Enums;

enum ReactionEnum: string {
    case Like = 'Like';
    case Dislike = 'Dislike';
    case Love = 'Love';
}
