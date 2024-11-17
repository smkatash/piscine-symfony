<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Psr\Log\LoggerInterface;

class PostVoter extends Voter
{
    const CREATE = 'POST_CREATE';
    const EDIT_OWN = 'POST_EDIT_OWN';
    const EDIT_ANY = 'POST_EDIT_ANY';
    const LIKE = 'POST_LIKE';
    const DISLIKE = 'POST_DISLIKE';
    const ADMIN = 'ADMIN';
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT_OWN, self::EDIT_ANY, self::LIKE, self::DISLIKE])
            && ($subject instanceof Post || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            return true;
        }

        $totalLikes = 0;
        $totalDislikes = 0;

        foreach ($user->getPosts() as $userPost) {
            $totalLikes += count($userPost->getLikedByUsers());
            $totalDislikes += count($userPost->getDislikedByUsers());
        }

        $reputation = $totalLikes - $totalDislikes;

        switch ($attribute) {
            case self::CREATE:
                return true;
            case self::EDIT_OWN:
                return $post->getAuthor() === $user;
            case self::EDIT_ANY:
                return $user !== $post->getAuthor() && $reputation >= 9;
            case self::LIKE:
                return $reputation >= 3;
            case self::DISLIKE:
                return $reputation >= 6;
        }

        return false;
    }
}


?>