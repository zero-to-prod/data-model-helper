<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;

/**
 * Provides helper methods for casting and mapping values for classes using the DataModel package.
 *
 * @link    https://github.com/zero-to-prod/data-model-helper
 *
 * @see     https://github.com/zero-to-prod/data-model
 * @see     https://github.com/zero-to-prod/data-model-factory
 * @see     https://github.com/zero-to-prod/transformable
 *
 * @package Zerotoprod\DataModelHelper
 */
trait DataModelHelper
{
    /**
     * Maps an array of values to instances of a specified type.
     *
     * ```
     * class User
     * {
     *  use \Zerotoprod\DataModel\DataModel;
     *  use \Zerotoprod\DataModelHelper\DataModelHelper;
     *
     *  #[Describe([
     *      'cast'    => [DataModelHelper::class, 'mapOf'], // Casting method to use
     *      'type'    => Alias::class,                      // Target type for each item
     *      'coerce'  => true,                              // Coerce single elements into an array
     *      'using'   => [User::class, 'map'],              // Custom mapping function
     *      'map_via' => 'mapper',                          // Custom mapping method (defaults to 'map')
     *      'level'   => 1,                                 // The dimension of the array. Defaults to 1.
     *      'key_by' => 'key',                              // Key an associative array by a field.
     *  ])]
     *  public Collection $Aliases;
     * }
     * ```
     *
     * @link    https://github.com/zero-to-prod/data-model-helper
     *
     * @see     https://github.com/zero-to-prod/data-model
     * @see     https://github.com/zero-to-prod/data-model-factory
     * @see     https://github.com/zero-to-prod/transformable
     *
     * @package Zerotoprod\DataModelHelper
     */
    public static function mapOf(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property)
    {
        $args = $Attribute?->getArguments()[0];
        $value = isset($args['coerce']) && !isset($value[0]) ? [$value] : $value;

        if (isset($args['using'])) {
            return ($args['using'])($value);
        }

        $method = $args['method'] ?? 'from';
        $type = $Property->getType()?->getName();
        $map = $args['map_via'] ?? 'map';

        $mapper = static function ($value, $level = 1) use ($args, $map, $type, $method, &$mapper) {
            return $type === 'array'
                ? array_map(static fn($item) => $level <= 1
                    ? $args['type']::$method($item)
                    : $mapper($item, $level - 1),
                    ($args['key_by'] ?? null) && count(array_column($value, ($args['key_by'] ?? null)))
                        ? array_combine(array_column($value, ($args['key_by'] ?? null)), $value)
                        : $value)
                : (new $type(
                    is_callable($args['map'] ?? null)
                        ? $args['map']($value)
                        : $value
                ))
                    ->$map(
                        fn($item) => $level <= 1
                            ? $args['type']::$method($item)
                            : $mapper($item, $level - 1)
                    );
        };

        return $mapper($value, $args['level'] ?? 1);
    }

    /**
     * Perform a regular expression search and replace.
     *
     * NOTE: If property allows null, null will be returned, else an empty string.
     *
     * ```
     *  #[Describe([
     *      'cast' => [self::class, 'pregReplace'],
     *      'pattern' => '/s/', // any regular expression
     *      'replacement' => '' // default
     *  ])]
     * ```
     */
    public static function pregReplace(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): array|string|null
    {
        if (!$value) {
            return $Property->getType()?->allowsNull()
                ? null
                : '';
        }
        $args = $Attribute?->getArguments()[0];

        return preg_replace($args['pattern'], $args['replacement'] ?? '', $value);
    }

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'isUrl'],
     *       'protocols' => ['http', 'udp'], // Optional. Defaults to all.
     *       'on_fail' => [MyAction::class, 'method'], // Optional. Invoked when validation fails.
     *       'exception' => InvalidUrlException::class, // Optional. Throws an exception when not url.
     *   ])]
     *  ```
     */
    public static function isUrl(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        if(!$value){
            return null;
        }

        $args = $Attribute?->getArguments()[0];
        if (!is_string($value)) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }

            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        $protocols = $args['protocols'] ?? [];

        $protocolList = empty($protocols)
            ? 'aaa|aaas|about|acap|acct|acd|acr|adiumxtra|adt|afp|afs|aim|amss|android|appdata|apt|ark|attachment|aw|barion|beshare|bitcoin|bitcoincash|blob|bolo|browserext|calculator|callto|cap|cast|casts|chrome|chrome-extension|cid|coap|coap\+tcp|coap\+ws|coaps|coaps\+tcp|coaps\+ws|com-eventbrite-attendee|content|conti|crid|cvs|dab|data|dav|diaspora|dict|did|dis|dlna-playcontainer|dlna-playsingle|dns|dntp|dpp|drm|drop|dtn|dvb|ed2k|elsi|example|facetime|fax|feed|feedready|file|filesystem|finger|first-run-pen-experience|fish|fm|ftp|fuchsia-pkg|geo|gg|git|gizmoproject|go|gopher|graph|gtalk|h323|ham|hcap|hcp|http|https|hxxp|hxxps|hydrazone|iax|icap|icon|im|imap|info|iotdisco|ipn|ipp|ipps|irc|irc6|ircs|iris|iris\.beep|iris\.lwz|iris\.xpc|iris\.xpcs|isostore|itms|jabber|jar|jms|keyparc|lastfm|ldap|ldaps|leaptofrogans|lorawan|lvlt|magnet|mailserver|mailto|maps|market|message|mid|mms|modem|mongodb|moz|ms-access|ms-browser-extension|ms-calculator|ms-drive-to|ms-enrollment|ms-excel|ms-eyecontrolspeech|ms-gamebarservices|ms-gamingoverlay|ms-getoffice|ms-help|ms-infopath|ms-inputapp|ms-lockscreencomponent-config|ms-media-stream-id|ms-mixedrealitycapture|ms-mobileplans|ms-officeapp|ms-people|ms-project|ms-powerpoint|ms-publisher|ms-restoretabcompanion|ms-screenclip|ms-screensketch|ms-search|ms-search-repair|ms-secondary-screen-controller|ms-secondary-screen-setup|ms-settings|ms-settings-airplanemode|ms-settings-bluetooth|ms-settings-camera|ms-settings-cellular|ms-settings-cloudstorage|ms-settings-connectabledevices|ms-settings-displays-topology|ms-settings-emailandaccounts|ms-settings-language|ms-settings-location|ms-settings-lock|ms-settings-nfctransactions|ms-settings-notifications|ms-settings-power|ms-settings-privacy|ms-settings-proximity|ms-settings-screenrotation|ms-settings-wifi|ms-settings-workplace|ms-spd|ms-sttoverlay|ms-transit-to|ms-useractivityset|ms-virtualtouchpad|ms-visio|ms-walk-to|ms-whiteboard|ms-whiteboard-cmd|ms-word|msnim|msrp|msrps|mss|mtqp|mumble|mupdate|mvn|news|nfs|ni|nih|nntp|notes|ocf|oid|onenote|onenote-cmd|opaquelocktoken|openpgp4fpr|pack|palm|paparazzi|payto|pkcs11|platform|pop|pres|prospero|proxy|pwid|psyc|pttp|qb|query|redis|rediss|reload|res|resource|rmi|rsync|rtmfp|rtmp|rtsp|rtsps|rtspu|s3|secondlife|service|session|sftp|sgn|shttp|sieve|simpleledger|sip|sips|skype|smb|sms|smtp|snews|snmp|soap\.beep|soap\.beeps|soldat|spiffe|spotify|ssh|steam|stun|stuns|submit|svn|tag|teamspeak|tel|teliaeid|telnet|tftp|tg|things|thismessage|tip|tn3270|tool|ts3server|turn|turns|tv|udp|unreal|urn|ut2004|v-event|vemmi|ventrilo|videotex|vnc|view-source|wais|webcal|wpid|ws|wss|wtai|wyciwyg|xcon|xcon-userid|xfire|xmlrpc\.beep|xmlrpc\.beeps|xmpp|xri|ymsgr|z39\.50|z39\.50r|z39\.50s'
            : implode('|', $protocols);

        /*
         * This pattern is derived from Symfony\Component\Validator\Constraints\UrlValidator (5.0.7).
         *
         * (c) Fabien Potencier <fabien@symfony.com> http://symfony.com
         */
        $pattern = '~^
            (PROTOCOLS)://                                 # protocol
            (((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+:)?((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+)@)?  # basic auth
            (
                ([\pL\pN\pS\-\_\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                                 # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                    # an IP address
                    |                                                 # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # an IPv6 address
            )
            (:[0-9]+)?                              # a port (optional)
            (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%[0-9A-Fa-f]{2})* )*          # a path
            (?:\? (?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%[0-9A-Fa-f]{2})* )?   # a query (optional)
            (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%[0-9A-Fa-f]{2})* )?       # a fragment (optional)
        $~ixu';

        $valid = preg_match(str_replace('PROTOCOLS', $protocolList, $pattern), $value) > 0;

        if (!$valid) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }
            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        return $value;
    }
}