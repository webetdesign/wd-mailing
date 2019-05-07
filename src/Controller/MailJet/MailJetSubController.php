<?php

namespace WebEtDesign\MailingBundle\Controller\MailJet;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

class MailJetSubController extends Controller
{

    public function subAction($btncolor = "btn-primary")
    {
        $sub = $this->createFormBuilder()
            ->add('email', EmailType::class, [

                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Votre email"
                ],
                "required" => true

            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Inscription',
                "attr" => [
                    "class" => ("input-group-button btn ".$btncolor)
                ]
            ])
            ->getForm();

        return $this->render('@WebEtDesignMailingBundle/Resources/views/form/subNewsletter.html.twig', [
            'sub' => $sub->createView(),
        ]);

    }

    public function validAction(Request $request)
    {
        $email = $request->request->get("email");

        if (!$email){
            return new JsonResponse(["errors" => "Vous devez remplir un email."]);
        }

        $validator = $this->container->get('validator');


        $emailConstraint = new Email([
            "message" => "Votre email n'est pas valide",
            "checkMX" => true,
        ]);

        $errors = $validator->validate(
            $email,
            $emailConstraint
        );

        if (count($errors) != 0){
            return new JsonResponse(["errors" => $errors->get(0)->getMessage()]);
        }

        $em = $this->container->get('doctrine.orm.entity_manager');

        $mailings = $em->getRepository(MailingEmailing::class)->findBy([
            "email" => $email
        ]);

        if ($mailings){
            return new JsonResponse(["errors" => "Vous êtes déjà inscrit"]);
        }

        $user = $em->getRepository(User::class)->findOneBy([
            "email" => $email
        ]);

        if ($user){
            $user->setEmailing(1);
            $em->flush();
            return new JsonResponse(["success" => "Votre profil à été modifié, vous êtes inscrit."]);
        }

        $mailing = new MailingEmailing();
        $mailing->setEmail($email);

        $em->persist($mailing);
        $em->flush();

        return new JsonResponse(["success" => "Vous êtes inscrit."]);

    }


}
