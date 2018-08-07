<?php
/**
 * Created by PhpStorm.
 * User: Matt
 */

namespace Freshdesk\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

/**
 * General Exception
 *
 * Thrown when the Freshdesk API returns an HTTP error code that isn't handled by other exceptions
 *
 * @package Exceptions
 * @author Matthew Clarkson <mpclarkson@gmail.com>
 */
class ApiException extends Exception
{

    /**
     * @internal
     * @param RequestException $e
     * @return AccessDeniedException|ApiException|AuthenticationException|ConflictingStateException|
     * MethodNotAllowedException|NotFoundException|RateLimitExceededException|UnsupportedAcceptHeaderException|
     * UnsupportedContentTypeException|ValidationException
     */
     public static function create(RequestException $e, $actualError = null) {

         if($response = $e->getResponse()) {
             
             switch ($response->getStatusCode()) {
                 case 400:
                     return new ValidationException($e, $actualError);
                 case 401:
                     return new AuthenticationException($e, $actualError);
                 case 403:
                     return new AccessDeniedException($e, $actualError);
                 case 404:
                     return new NotFoundException($e, $actualError);
                 case 405:
                     return new MethodNotAllowedException($e, $actualError);
                 case 406:
                     return new UnsupportedAcceptHeaderException($e, $actualError);
                 case 409:
                     return new ConflictingStateException($e, $actualError);
                 case 415:
                     return new UnsupportedContentTypeException($e, $actualError);
                 case 429:
                     return new RateLimitExceededException($e, $actualError);
             }
         }

         return new ApiException($e);
    }

    /**
     * @var RequestException
     * @internal
     */
    private $exception;

    /**
     * Returns the Request Exception
     *
     * A Guzzle Request Exception is returned
     *
     * @return RequestException
     */
    public function getRequestException()
    {
        return $this->exception;
    }

    /**
     * Exception constructor
     *
     * Constructs a new exception.
     *
     * @param RequestException $e
     * @internal
     */
    public function __construct(RequestException $e, $message = null)
    {
        $this->exception = $e;
        parent::__construct($message);
    }
}
