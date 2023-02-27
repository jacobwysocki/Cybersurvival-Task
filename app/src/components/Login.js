import React, {useState, useEffect} from 'react';

import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';

/**
 * Login component.
 * 
 * @author Jakub Wysocki 
 */

function Login(props) {

    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    

/*
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


        fetch("",
            {
                method: 'POST',
                headers: new Headers({"Authorization": "Basic " + encodedString})
            })
            .then(
                (response) => {
                    return response.json()
                }
            )
            .then(
                (json) => {
                    if (json.message === "Success") {
                        props.handleAuthenticated(true);
                        localStorage.setItem('token', json.data.token);
                    } else if (json.message === "Invalid Credentials.") {
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
    */

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
                <h1>Admin Page</h1>
                <br/>
                <Button className="buttonSignOut"
                        variant="dark"
                        type="submit"
                        onClick={handleSignOut}>
                    Sign out
                </Button>
            
                
                
            </div>
            }
            {!props.authenticated && <div>
                <h2>Sign in</h2>
                <div className="formArea">
                    <div className="form-container">
                        <FloatingLabel
                            controlId="floatingInput"
                            label="Username"
                            className="mb-3">
                            <Form.Control
                                type="text"
                                placeholder="jakub123"
                                />
                        </FloatingLabel>

                        <FloatingLabel
                            controlId="floatingPassword"
                            label="Password">
                            <Form.Control
                                type="password"
                                placeholder="Password"
                                />
                        </FloatingLabel>
                        <br/>
                        {errorMessage.length > 0 ?
                            <Alert variant="danger">
                                {errorMessage}
                            </Alert>
                            : null}

                        <Button className="button"
                                variant="dark"
                                type="submit">
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