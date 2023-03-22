import React, {useState, useEffect} from 'react';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { Buffer } from 'buffer';
import {Routes, Route, Navigate} from 'react-router-dom';


/**
 * Login component.
 * 
 * @author Jakub Wysocki 
 */

function Login(props) {

    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])

    const handleUsername = (event) => {
        setUsername(event.target.value);
    }

    const handlePassword = (event) => {
        setPassword(event.target.value);
    }


    const handleSubmit = () => {
        const encodedString = Buffer.from(
            username + ":" + password
        ).toString('base64');


        fetch("http://localhost:8080/api/auth",
            {
                method: 'POST',
                headers: new Headers( { "Authorization": "Basic " +encodedString })
            })
            .then(
                (response) => {
                    return response.json()
                }
            )
            .then(
                (json) => {
                    if (json.message === "Successfully logged in") {
                        props.handleAuthenticated(true);
                        localStorage.setItem('username', username);
                        localStorage.setItem('password', password);
                        localStorage.setItem('token', json.data.token);
                        localStorage.setItem('username', username);
                        localStorage.setItem('password', password);
                    }
                    else if (json.message === "Invalid Credentials.") {
                        setErrorMessage("Invalid Username or Password")
                    }
                }
            )
            .catch(
                (e) => {
                    console.log(e.message)
                }
            )
    }


    const handleSignOut = () => {
        props.handleAuthenticated(false)
        setPassword("");
        setUsername("");
        localStorage.removeItem('token')
        setErrorMessage("");
    }


    return (
        <div>
            {props.authenticated && <div>
                <Navigate replace to="/dashboard"/>
            </div>
            }
            {!props.authenticated && <div>
                <h2>Sign in</h2>
                <div className="formArea">
                    <div className="form-container">
                            <Form.Control
                                type="text"
                                placeholder="Email"
                                onChange={handleUsername}/>

                            <Form.Control
                                type="password"
                                placeholder="Password"
                                onChange={handlePassword}/>

                        <br/>
                        {errorMessage.length > 0 ?
                            <Alert variant="danger">
                                {errorMessage}
                            </Alert>
                            : null}

                        <Button className="button"
                                variant="dark"
                                type="submit"
                                onClick={handleSubmit}
                                disabled={!username || !password}>
                            Sign in
                        </Button>
                    </div>
                </div>
            </div>
            }
        </div>
    )
}

export default Login;