<?php
/**
 * @package Newscoop
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\NewscoopBundle\Extension;

use Newscoop\NewscoopBundle\Extension\AsseticExtension;
use NoiseLabs\Bundle\SmartyBundle\Extension\DynamicAsseticExtension as SmartyDynamicAsseticExtension;

/**
 * The "dynamic" reincarnation of AsseticExtension.
 *
 * @author Vítor Brandão <vitor@noiselabs.com>
 */
class DynamicAsseticExtension extends SmartyDynamicAsseticExtension
{}