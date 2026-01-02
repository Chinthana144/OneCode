<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps|null $camps
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampUsers whereUserId($value)
 */
	class CampUsers extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $contactPerson
 * @property string $contactPhone
 * @property string $contactEmail
 * @property string $mikritikIP
 * @property string $mikritikPort
 * @property string $mikrotikUsername
 * @property string $mikrotikPassword
 * @property string $radiusSecret
 * @property string $radiusIP
 * @property string $monthly_target
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CampUsers> $campusers
 * @property-read int|null $campusers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientSubscriptions> $clientSubscriptions
 * @property-read int|null $client_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customers> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Packages> $packages
 * @property-read int|null $packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriptions> $subscription
 * @property-read int|null $subscription_count
 * @method static \Illuminate\Database\Eloquent\Builder|Camps newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camps newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camps query()
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereMikritikIP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereMikritikPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereMikrotikPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereMikrotikUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereMonthlyTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereRadiusIP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereRadiusSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camps whereUpdatedAt($value)
 */
	class Camps extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property int $customer_id
 * @property int $package_id
 * @property int $paymethod_id
 * @property string $purchaseDate
 * @property string $purchaseDateTime
 * @property string|null $subscriptionStartTime
 * @property string|null $subscriptionEndTime
 * @property string $price
 * @property string $macAddress
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps|null $camp
 * @property-read \App\Models\Customers|null $customer
 * @property-read \App\Models\Packages|null $package
 * @property-read \App\Models\Paymethods|null $paymethod
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereMacAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions wherePaymethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions wherePurchaseDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereSubscriptionEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereSubscriptionStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSubscriptions whereUserId($value)
 */
	class ClientSubscriptions extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $customerType
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CustomerType> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CustomerType> $packages
 * @property-read int|null $packages_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerType whereUpdatedAt($value)
 */
	class CustomerType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $camp_id
 * @property int $customerType_id
 * @property string $fullname
 * @property string $phone
 * @property string|null $email
 * @property string $username
 * @property string $password
 * @property string|null $mac_address
 * @property int $status
 * @property string|null $login_datetime
 * @property string|null $expiry_datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps|null $camp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientSubscriptions> $clientSubscription
 * @property-read int|null $client_subscription_count
 * @property-read \App\Models\CustomerType|null $customerType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriptions> $subscription
 * @property-read int|null $subscription_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customers query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereCustomerTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereExpiryDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereLoginDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereMacAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customers whereUsername($value)
 */
	class Customers extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $camp_id
 * @property int $customerType_id
 * @property string $name
 * @property int $duration
 * @property string $price
 * @property string $bandwidth
 * @property int $downloadlimit
 * @property int $uploadlimit
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps|null $camp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientSubscriptions> $clientSubscription
 * @property-read int|null $client_subscription_count
 * @property-read \App\Models\CustomerType|null $customerType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriptions> $subscription
 * @property-read int|null $subscription_count
 * @method static \Illuminate\Database\Eloquent\Builder|Packages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Packages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Packages query()
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereBandwidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereCustomerTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereDownloadlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Packages whereUploadlimit($value)
 */
	class Packages extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $page_id
 * @property int $camp_id
 * @property int $create
 * @property int $view
 * @property int $edit
 * @property int $delete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps $camp
 * @property-read \App\Models\Pages $page
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageAccess whereView($value)
 */
	class PageAccess extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pagename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PageAccess> $pageaccess
 * @property-read int|null $pageaccess_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pages query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pages wherePagename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pages whereUpdatedAt($value)
 */
	class Pages extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $paymethod_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriptions> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods query()
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods wherePaymethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paymethods whereUpdatedAt($value)
 */
	class Paymethods extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles query()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereUpdatedAt($value)
 */
	class Roles extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property int $customer_id
 * @property int $package_id
 * @property int $paymethod_id
 * @property string $purchaseDate
 * @property string $purchaseDateTime
 * @property string|null $subscriptionStartTime
 * @property string|null $subscriptionEndTime
 * @property string $price
 * @property string $macAddress
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camps|null $camp
 * @property-read \App\Models\Customers|null $customer
 * @property-read \App\Models\Packages|null $package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereMacAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions wherePaymethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions wherePurchaseDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereSubscriptionEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereSubscriptionStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriptions whereUserId($value)
 */
	class Subscriptions extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property int $role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CampUsers> $campusers
 * @property-read int|null $campusers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientSubscriptions> $clientSubscriptions
 * @property-read int|null $client_subscriptions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Roles|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriptions> $subscription
 * @property-read int|null $subscription_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

