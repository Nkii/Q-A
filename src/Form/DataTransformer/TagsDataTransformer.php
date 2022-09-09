<?php
/**
 * TagDateTransformer.
 */
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class TagsDataTransformer
 */
class TagsDataTransformer implements DataTransformerInterface
{
    private TagRepository $tagRepository;

    /**
     * TagsDataTransformer constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }


    public function transform($tags)
    {
        if (null === $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(',', $tagNames);
    }


    public function reverseTransform($value)
    {
        $tagNames = explode(',', $value);
        $tags = [];

        foreach ($tagNames as $tagName) {
            if ('' === trim($tagName)) {
                continue;
            }

            $tagName = strtolower($tagName);

            $tag = $this->tagRepository->findOneByTitle($tagName);
            if (null === $tag) {
                $tag = new Tag();
                $tag->setName($tagName);
                $this->tagRepository->add($tag);
            }
            $tags[] = $tag;
        }

        return $tags;
    }
}
