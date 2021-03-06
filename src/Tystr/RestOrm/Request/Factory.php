<?php

namespace Tystr\RestOrm\Request;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Tystr\RestOrm\Metadata\Registry;
use GuzzleHttp\Psr7\Request;
use Tystr\RestOrm\Exception\InvalidArgumentException;
use Tystr\RestOrm\UrlGenerator\UrlGeneratorInterface;

class Factory
{
    /**
     * @var Registry
     */
    private $metadataRegistry;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $format;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param string                $format
     * @param Registry              $metadataRegistry
     * @param SerializerInterface   $serializer
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        $format,
        Registry $metadataRegistry = null,
        SerializerInterface $serializer = null
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->format = $format;
        $this->metadataRegistry = $metadataRegistry ?: new Registry();
        $this->serializer = $serializer ?: SerializerBuilder::create()->build();
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Returns a POST request if the object's identifier is null; a PUT request is returned otherwise.
     *
     * @param object $object
     * @param array  $parameters
     *
     * @return Request
     */
    public function createSaveRequest($object, array $parameters = [])
    {
        $metadata = $this->metadataRegistry->getMetadataForClass(get_class($object));
        if (null === $id = $metadata->getIdentifierValue($object)) {
            // Identifier is null so this is a new entity
            return new Request(
                'POST',
                $this->urlGenerator->getCreateUrl($metadata->getResource(), $parameters),
                ['Content-Type' => $this->getContentTypeHeader()],
                $this->serializer->serialize($object, $this->format, SerializationContext::create()->setGroups('Default'))
            );
        }

        // Identifier is set so we are modifying an exiting entity
        return new Request(
            'PUT',
            $this->urlGenerator->getModifyUrl($metadata->getResource(), $id, $parameters),
            ['Content-Type' => $this->getContentTypeHeader()],
            $this->serializer->serialize($object, $this->format, SerializationContext::create()->setGroups('Default'))
        );
    }

    /**
     * @param string $class
     * @param string $id
     * @param array  $parameters
     *
     * @return Request
     */
    public function createFindOneRequest($class, $id, array $parameters = [])
    {
        $metadata = $this->metadataRegistry->getMetadataForClass($class);

        return new Request(
            'GET',
            $this->urlGenerator->getFindOneUrl($metadata->getResource(), $id, $parameters),
            ['Content-Type' => $this->getContentTypeHeader()]
        );
    }

    /**
     * @param string $class
     * @param array  $parameters
     *
     * @return Request
     */
    public function createFindAllRequest($class, array $parameters = [])
    {
        $metadata = $this->metadataRegistry->getMetadataForClass($class);

        return new Request(
            'GET',
            $this->urlGenerator->getFindAllUrl($metadata->getResource(), $parameters),
            ['Content-Type' => $this->getContentTypeHeader()]
        );
    }

    /**
     * @param object $object
     * @param array  $parameters
     *
     * @return Request
     */
    public function createDeleteRequest($object, array $parameters = [])
    {
        $metadata = $this->metadataRegistry->getMetadataForClass(get_class($object));

        return new Request(
            'DELETE',
            $this->urlGenerator->getRemoveUrl(
                $metadata->getResource(),
                $metadata->getIdentifierValue($object),
                $parameters
            ),
            ['Content-Type' => $this->getContentTypeHeader()]
        );
    }

    /**
     * @return string
     */
    public function getContentTypeHeader()
    {
        switch ($this->format) {
            case 'json':
                return 'application/json';
            case 'xml':
                return 'application/xml';
            default:
                throw new InvalidArgumentException(sprintf('Unsupported format "%s".', $this->format));
        }
    }
}
