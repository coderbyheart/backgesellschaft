<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m
 * Date: 22.09.13
 * Time: 19:39
 * To change this template use File | Settings | File Templates.
 */

namespace Backgesellschaft\BackendBundle\Entity\Newsletter;

interface SubscriptionRepository
{
    /**
     * @param string $email
     *
     * @return \PhpOption\Option
     */
    public function getSubscription($email);

    /**
     * @param string $id
     * @param string $key
     *
     * @return \PhpOption\Option
     */
    public function getSubscriptionByIdAndKey($id, $key);

    /**
     * @return Subscription[]
     */
    public function getNewSubscriptions();
}