<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 12/04/2017
 * Time: 11:43
 */

namespace AppBundle\Services;


use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;


class JsonExportImportData
{
    private $em;
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @param $groupName
     * @param $jsonDatas
     * @param $entityType
     *
     * @return bool|string - Error status
     */
    public function import($groupName, $jsonDatas, $entityType){

        $context = new DeserializationContext();
        $context->setGroups($groupName);

        if(is_array(json_decode($jsonDatas))){
            $entities = $this->serializer->deserialize($jsonDatas, "ArrayCollection<$entityType>", 'json', $context);
            foreach($entities as $entity){
                $this->em->merge($entity);
            }
        }else{
            $entity = $this->serializer->deserialize($jsonDatas, $entityType, 'json', $context);
            $this->em->merge($entity);
        }

        try{
            $this->em->flush();
        }catch (UniqueConstraintViolationException $exception){
            return "Duplicate entry : name field should be unique!";
        }
        catch (NotNullConstraintViolationException $exception){
            return "A field value is missing or illegally set to null";//.$exception->getMessage()
        }

        return false;
    }

    public function export($groupName, $object = null){

        $serializerContext = new SerializationContext();
        $serializerContext->setGroups($groupName);

        $json = $this->serializer->serialize($object, 'json', $serializerContext);

        $response = new StreamedResponse(
            function () use ($json) {
                echo $json;
            }
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Cache-Control', '');
        $response->headers->set('Content-Length', strlen($json));
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s'));
        $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, str_replace("/", "", $object->getName()).' - export.json');
        $response->headers->set('Content-Disposition', $contentDisposition);


        return $response;
    }

    public function exportAll($groupName, $entityType, $entityName){

        $serializerContext = new SerializationContext();
        $serializerContext->setGroups($groupName);

        $entities = $this->em->getRepository("$entityType")->findAll();
        $json = $this->serializer->serialize($entities, 'json', $serializerContext);

        $response = new StreamedResponse(
            function () use ($json) {
                echo $json;
            }
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Cache-Control', '');
        $response->headers->set('Content-Length', strlen($json));
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s'));
        $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, "All $entityName - export.json");
        $response->headers->set('Content-Disposition', $contentDisposition);

        return $response;
    }
}