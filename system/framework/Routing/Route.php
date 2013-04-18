<?php
/**
 * Plexis Content Management System
 *
 * @file        system/framework/Routing/Route.php
 * @copyright   2013, Plexis Dev Team
 * @license     GNU GPL v3
 * @contains    Route
 */
namespace System\Routing;

/**
 * A Route object, used to compare URI's for route matches
 *
 * @author      Steven Wilson 
 * @package     Core
 * @subpackage  Router
 */
class Route
{
    /**
     * The regex match for this route.
     * @var string
     */
    protected $match;
    
    /**
     * The replacement for the match, in a route that successfully
     * tests against the match
     * @var string
     */
    protected $replace;

    /**
     *  Class Constructor.
     *
     * @param string $match The regular expression to test routes against
     * @param bool|string|\string[] $replace a replacement route, or an array or 2 routes,
     *   the first being the replacement for a normal route, and the second
     *   for ajax requests.
     */
    public function __construct($match, $replace = false)
    {
        $this->match = str_replace(
            array(
                ':any',
                ':alnum',
                ':num',
                ':alpha',
                ':segment',
            ), array(
                '.*',
                '[[:alnum:]]+',
                '[[:digit:]]+',
                '[[:alpha:]]+',
                '[^/]*',
            ), $match
        );
        $this->replace = $replace;
    }
    
    /**
     *  Tests the passed route against the routes regular expression for a match.
     *
     * @param string $route The URI route to test.
     * @param string[] $data [Reference Variable] Returns the controller, action, and
     *   params if the route is a match.
     *
     * @return bool Returns true if the route is a match for the Route's regular expression,
     *   false otherwise.
     */
    public function match($route, &$data = array())
    {
        if(preg_match('#^'. $this->match .'$#i', $route))
        {
            $ajax = null;
            if(!empty($this->replace))
            {
                if(is_array($this->replace))
                {
                    $route = preg_replace('#^'. $this->match .'$#i', $this->replace[0], $route);
                    $ajax = (isset($this->replace[1])) ? preg_replace('#^'. $this->match .'$#i', $this->replace[1], $route) : null;
                }
                else
                    $route = preg_replace('#^'. $this->match .'$#i', $this->replace, $route);
            }
            
            // Build basic uri data
            $parts = explode('/', rtrim($route, '/'));
            $data = array(
                'module' => $parts[0],
                'controller' => isset($parts[1]) ? ucfirst($parts[1]) : ucfirst($parts[0]),
                'action' => isset($parts[2]) ? $parts[2] : 'index',
                'params' => array_slice($parts, 3)
            );
            
            // Ajax
            if($ajax != null)
            {
                $parts = explode('/', rtrim($ajax, '/'));
                $data += array(
                    'ajax' => array(
                        'module' => $parts[0],
                        'controller' => isset($parts[1]) ? ucfirst($parts[1]) : ucfirst($parts[0]),
                        'action' => isset($parts[2]) ? $parts[2] : 'index',
                        'params' => array_slice($parts, 3)
                    )
                );
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     *  Returns the regular expression for this route.
     *
     * @return string
     */
    public function getMatch()
    {
        return $this->match;
    }
    
    /**
     *  Returns the replacements for this route.
     *
     * @return string
     */
    public function getReplacement()
    {
        return $this->replace;
    }
}