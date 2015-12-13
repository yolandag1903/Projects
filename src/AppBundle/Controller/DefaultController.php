<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {        
        return $this->render('AppBundle:CashMachine:withdraw.html.twig',array('money_to_give' => null,
                                                                              'monto_retiro'  => null));
    }
    
    /**
     * @Route("/withdraw", name="withdraw")
     * @Method("POST")
     * @Template("AppBundle:CashMachine:withdraw.html.twig")
     */
    public function withdrawAction(Request $request)
    {
        $withdraw    = $request->get('withdraw'); 
        $number_card = $request->get('card_number'); 
        $monto_retiro = $withdraw;
        
        $bill = array(100, 50 , 20, 10);
         
        $money_to_give = array(); 
      
        if($withdraw!=NULL AND $number_card!=NULL)
        {
            if(is_numeric($withdraw) AND is_numeric($number_card)) {
                if(($withdraw%$bill[3]) == 0){
                    for($i=0; $i<count($bill); $i++){               
                        $count = 0 ;
                        $money_to_give[$i] =0;
                        while($withdraw >= $bill[$i]){
                            $count=$count+1;
                            $money_to_give[$i]=$count.' de $'.$bill[$i];
                            $withdraw=$withdraw-$bill[$i];                      
                        }                                             
                    }
                }else{        
                    $this->get('session')->getFlashBag()->set(
                                                                'error',
                                                                'Debe ingresar un monto múltiplo de 10 para retirar.'                                                        
                                                            );    
                }
            }else{
                $this->get('session')->getFlashBag()->set(
                                                            'error',
                                                            'Debe indicar un valor numérico en los campos Number card y Withdraw.'                                                        
                                                        );
            }
        }else{
            $this->get('session')->getFlashBag()->set(
                                                            'error',
                                                            'Debe ingresar Number card y Withdraw.'                                                        
                                                        );
        }
        return $this->render('AppBundle:CashMachine:withdraw.html.twig', array('money_to_give' => $money_to_give,
                                                                               'monto_retiro'  => $monto_retiro));        
    }
}