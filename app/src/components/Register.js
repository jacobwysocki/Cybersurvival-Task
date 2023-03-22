import React, {useState, setState, useEffect} from 'react';

import '../App.css';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import {json, Link, useNavigate} from "react-router-dom";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";




/**
 * Register component.
 * 
 * @author Jakub Wysocki 
 */

function Register(props) {

    const [firstName, setFirstName] = useState(null);
    const [lastName, setLastName] = useState(null);
    const [email, setEmail] = useState(null);
    const [password,setPassword] = useState(null);
    const [confirmPassword,setConfirmPassword] = useState(null);
    const [jobRole,setJobRole] = useState(null);
    const [userType,setUserType] = useState(null);
    const [registered,setRegistered] = useState(false);
    const [errorMessage,setErrorMessage] = useState("");
    const navigate = useNavigate();


    const handleSubmit = () => {
        console.log(firstName,lastName,email,password,confirmPassword, jobRole, userType);
        if(password !== confirmPassword){
            setErrorMessage("Passwords do not match");
        }
        else {

            fetch('http://localhost:8080/api/register.php',
                {
                    method: 'POST',
                    body: JSON.stringify({
                        "firstName": firstName,
                        "lastName": lastName,
                        "email": email,
                        "password": password,
                        "jobRole": jobRole,
                        "rankID": userType
                    }),
                })
                .then(
                    (response) => {
                        return response.json()
                    }
                )

                .then((json) => {
                    console.log(json);
                    if (json.message === "You have successfully registered.") {
                        setRegistered(true);
                        navigate('/login');
                    }
                    else if (json.message === "Invalid Email Address!") {
                        setErrorMessage("Invalid Email address!");
                    }
                    else if (json.message === "SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: users.email") {
                        setErrorMessage("Email already exists!");
                    }
                    else if (json.message === "Your password must be at least 8 characters long!"){
                        setErrorMessage("Your password must be at least 8 characters long!");
                    }
                }
                )
                .catch((error) => {
                    console.error(error);
                });
        }
    };

    const handleInputChange = (e) => {
        const {id , value} = e.target;
        if(id === "firstName"){
            setFirstName(value);
        }
        if(id === "lastName"){
            setLastName(value);
        }
        if(id === "email"){
            setEmail(value);
        }
        if(id === "password"){
            setPassword(value);
        }
        if(id === "confirmPassword"){
            setConfirmPassword(value);
        }
        if(id === "jobRole"){
            setJobRole(value);
        }
        if(id === "userType"){
            setUserType(value);
        }

    }


    return (
        <>
        <h1>Registration Form</h1>
        <div className= "registerForm">
        <Container>
        <Form>
        <Row>
        <Col><Form.Group className="mb-3">
            <Form.Label>First Name</Form.Label>
            <Form.Control type="text" onChange = {(e) => handleInputChange(e)} id="firstName" placeholder="Enter first name" />
        </Form.Group>
        </Col>

        <Col><Form.Group className="mb-3">
            <Form.Label>Last Name</Form.Label>
            <Form.Control type="text" id="lastName" onChange = {(e) => handleInputChange(e)} placeholder="Enter last name" />
        </Form.Group>
        </Col>

      </Row>

      <Row>
          <Form.Group className="mb-3">
              <Form.Label>Type of Account</Form.Label>
              <Form.Select defaultValue="" id="userType" onChange={(e) => handleInputChange(e)}>
                  <option value="" disabled>Select type of account</option>
                  <option value="2">Administrator</option>
                  <option value="1">Participant</option>
              </Form.Select>
          </Form.Group>

      <Form.Group className="mb-3">
            <Form.Label>Job Role</Form.Label>
            <Form.Control type="text" id="jobRole" onChange = {(e) => handleInputChange(e)} placeholder="Enter job role" />
        </Form.Group>

        <Col><Form.Group className="mb-3">
            <Form.Label>Email address</Form.Label>
            <Form.Control type="email" id="email" onChange = {(e) => handleInputChange(e)} placeholder="Enter email" />
        </Form.Group>
        

        <Form.Group className="mb-3">
            <Form.Label>Password</Form.Label>
            <Form.Control type="password" id="password" onChange = {(e) => handleInputChange(e)} placeholder="Password" />
            <Form.Text className="text-muted">
            Use a strong password.
            </Form.Text>
        </Form.Group>
        
        <Form.Group className="mb-3">
            <Form.Label>Confirm Password</Form.Label>
            <Form.Control type="password" id="confirmPassword" onChange = {(e) => handleInputChange(e)} placeholder="Confirm Password" />
        </Form.Group>

        </Col>
        
      </Row>
            {errorMessage.length > 0 ?
                <Alert variant="danger">
                    {errorMessage}
                </Alert>
                : null}
            {registered === true ?
                <Alert variant="success">
                    You have successfully registered.
                </Alert>
                : null}
        <Button variant="dark"
                onClick={handleSubmit}
                disabled={!firstName || !lastName || !jobRole || !email || !password || !confirmPassword || !userType}>
            Register
        </Button>
            <div  className="d-flex flex-column justify-content-between min-vh-100">
                <Link to="/">
                    <Button className="button mt-2" variant="secondary">
                        <FontAwesomeIcon icon={faArrowLeft} />  Back to home
                    </Button>
                </Link>
            </div>

        </Form>
    </Container>
        </div>
        </>
    )
}

export default Register;


        