import React, {useState, setState, useEffect} from 'react';

import '../App.css';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import {json, Link} from "react-router-dom";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';

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
    

    const handleSubmit = () => {
        console.log(firstName,lastName,email,password,confirmPassword, jobRole);
        
    
        
        fetch('http://localhost/api/register.php', {
            method: 'POST',
            body: JSON.stringify({"firstName":firstName, "lastName":lastName, "email":email, "password":password, "jobRole":jobRole}),
        })
            .then((response) => response.text())
            .then((text) => console.log(text))
            .catch((error) => {
                console.error(error);
                
            });
            console.log();
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
            <Form.Control type="text" value={firstName} onChange = {(e) => handleInputChange(e)} id="firstName" placeholder="Enter first name" />
        </Form.Group>
        </Col>

        <Col><Form.Group className="mb-3">
            <Form.Label for="lastName">Last Name</Form.Label>
            <Form.Control type="text" id="lastName" value={lastName} onChange = {(e) => handleInputChange(e)} placeholder="Enter last name" />
        </Form.Group>
        </Col>

      </Row>

      <Row>
      <Form.Group className="mb-3">
            <Form.Label for="jobRole">Job Role</Form.Label>
            <Form.Control type="text" id="jobRole" value={jobRole} onChange = {(e) => handleInputChange(e)} placeholder="Enter job role" />
        </Form.Group>

        <Col><Form.Group className="mb-3">
            <Form.Label for="email">Email address</Form.Label>
            <Form.Control type="email" id="email" value={email} onChange = {(e) => handleInputChange(e)} placeholder="Enter email" />
        </Form.Group>
        

        <Form.Group className="mb-3">
            <Form.Label for="password">Password</Form.Label>
            <Form.Control type="password" id="password" value={password} onChange = {(e) => handleInputChange(e)} placeholder="Password" />
            <Form.Text className="text-muted">
            Use a strong password.
            </Form.Text>
        </Form.Group>
        
        <Form.Group className="mb-3">
            <Form.Label for="confirmPassword">Confirm Password</Form.Label>
            <Form.Control type="password" id="confirmPassword" value={confirmPassword} onChange = {(e) => handleInputChange(e)} placeholder="Confirm Password" />
        </Form.Group>

        </Col>
        
      </Row>    
        <Button variant="dark" onClick={handleSubmit} >
            Register
        </Button>
        </Form>
    </Container>
            {/* <form>
                <input type="text"></input>
                <Button variant="dark" onClick={handleSubmit} >
                    Register
                </Button>
            </form> */}
        </div>
        

        
        </>
    )
}

export default Register;


        