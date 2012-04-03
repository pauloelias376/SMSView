<?php
/**
 * File containing the ezcWebdavMultistatusResponse class.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Webdav
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */
/**
 * Class generated by the backend to indicate multiple responses at once.
 *
 * If a {@link ezcWebdavBackend} needs to indicate multiple stati at once (like
 * multiple errors or a mixture of success messages and error) an instance of
 * {@link ezcWebdavMultiStatusRequest} is generated.
 *
 * @version //autogentag//
 * @package Webdav
 */
class ezcWebdavMultistatusResponse extends ezcWebdavResponse
{
    /**
     * Creates a new response object.
     *
     * Any number of {@link ezcWebdavResponse} objects may be passed as
     * parameters to the constructer.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct( ezcWebdavResponse::STATUS_207 );

        $params = func_get_args();
        $responses = array();

        foreach ( $params as $param )
        {   
            // Flatten array structure, if given
            if ( is_array( $param ) )
            {
                foreach ( $param as $value )
                {
                    $responses[] = $value;
                }

                continue;
            }

            // Just add everything else
            $responses[] = $param;
        }

        $this->responses = $responses;
    }

    /**
     * Validates the headers set in this response.
     *
     * This method is called by {@link ezcWebdavServer} after the response
     * object has been created by an {@link ezcWebdavBackend}. It validates all
     * headers, specific to this response, for existance of required headers
     * and validity of all headers used. The call of the parent method is
     * *mandatory* to have common WebDAV and HTTP headers validated, too.
     *
     * @return void
     *
     * @throws ezcWebdavMissingHeaderException
     *         if a required header is missing.
     * @throws ezcWebdavInvalidHeaderException
     *         if a header is present, but its content does not validate.
     */
    public function validateHeaders()
    {
        if ( count( $this->responses ) > 0 && $this->getHeader( 'Content-Type' ) === null )
        {
            throw new ezcWebdavMissingHeaderException( 'Content-Type' );
        }
        parent::validateHeaders();
    }

    /**
     * Sets a property.
     *
     * This method is called when an property is to be set.
     * 
     * @param string $propertyName The name of the property to set.
     * @param mixed $propertyValue The property value.
     * @return void
     * @ignore
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the given property does not exist.
     * @throws ezcBaseValueException
     *         if the value to be assigned to a property is invalid.
     * @throws ezcBasePropertyPermissionException
     *         if the property to be set is a read-only property.
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'responses':
                if ( !is_array( $propertyValue ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'array( ezcWebdavResponse )' );
                }

                ( count( $propertyValue ) > 0 ? $this->setHeader( 'Content-Type', 'text/xml; charset="utf-8"' ) : $this->setHeader( 'Content-Type', null ) );

                $this->properties[$propertyName] = $propertyValue;
                break;

            default:
                parent::__set( $propertyName, $propertyValue );
        }
    }
}

?>