import React, {useState, useEffect} from 'react';
import { Navigate } from "react-router-dom";
import Button from "react-bootstrap/Button";
import Table from 'react-bootstrap/Table';
import {Buffer} from "buffer";
import Container from "react-bootstrap/Container";
import Form from "react-bootstrap/Form";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Alert from "react-bootstrap/Alert";

/**
 * Manage Account component for logged in participants.
 *
 * @author Jakub Wysocki
 */

function ManageAccount(props) {

    const [firstName, setFirstName] = useState(null);
    const [lastName, setLastName] = useState(null);
    const [email, setEmail] = useState(null);
    const [password,setPassword] = useState(null);
    const [confirmPassword,setConfirmPassword] = useState(null);
    const [jobRole,setJobRole] = useState(null);
    const [userType,setUserType] = useState(null);
    const [registered,setRegistered] = useState(false);
    const [errorMessage,setErrorMessage] = useState("");
    const currentUserID = localStorage.getItem('userID');


    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])

    const [currentUser, setCurrentUser] = useState([]);

    const token = localStorage.getItem('token');
    console.log(currentUserID);
    useEffect( () => {
        fetch("http://localhost:8080/api/users/" + currentUserID,
            {
                method: 'GET',
                headers: {"Authorization": "Bearer " + token}
            })
            .then(
                (response) => response.json()
            ).then(
            data => setCurrentUser(data)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);

    const handleSubmit = () => {
        console.log(firstName,lastName,email,password,confirmPassword, jobRole);
        if(password !== confirmPassword){
            setErrorMessage("Passwords do not match");
        }
        else {

            const updatedUserData = {
                "userID": currentUserID,
                "firstName": firstName || currentUser.firstName,
                "lastName": lastName || currentUser.lastName,
                "email": email || currentUser.email,
                "password": password || currentUser.password,
                "jobRole": jobRole || currentUser.jobRole,
            };

            fetch('http://localhost:8080/api/users/' + currentUserID,
                {
                method: 'PUT',
                headers: { "Authorization": "Bearer " + token },
                body: JSON.stringify(updatedUserData)
            })
                .then(response => {
                    if (response.ok) {
                        // update the current user state with the updated data
                        setCurrentUser(updatedUserData);
                        // set a success message or redirect to another page
                        setRegistered(true);
                    } else {
                        throw new Error('Failed to update user');
                    }
                })
                .catch(error => {
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

    }


    return(
        <div>
            {props.authenticated &&
                <div>
                    <h2>Account</h2>
                    <Table striped bordered hover variant="dark">
                        <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Job Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{currentUser.firstName}</td>
                            <td>{currentUser.lastName}</td>
                            <td>{currentUser.email}</td>
                            <td>{currentUser.jobRole}</td>
                        </tr>
                        </tbody>
                    </Table>
                    <h2>Change Details</h2>
                    <div className= "registerForm">
                        <Container>
                            <Form>
                                <Row>
                                    <Col><Form.Group className="mb-3">
                                        <Form.Label>First Name</Form.Label>
                                        <Form.Control type="text" onChange = {(e) => handleInputChange(e)} id="firstName" placeholder={currentUser.firstName} />
                                    </Form.Group>
                                    </Col>

                                    <Col><Form.Group className="mb-3">
                                        <Form.Label>Last Name</Form.Label>
                                        <Form.Control type="text" id="lastName" onChange = {(e) => handleInputChange(e)} placeholder={currentUser.lastName} />
                                    </Form.Group>
                                    </Col>

                                </Row>

                                <Row>
                                    <Form.Group className="mb-3">
                                        <Form.Label>Job Role</Form.Label>
                                        <Form.Control type="text" id="jobRole" onChange = {(e) => handleInputChange(e)} placeholder={currentUser.jobRole} />
                                    </Form.Group>

                                    <Col><Form.Group className="mb-3">
                                        <Form.Label>Email address</Form.Label>
                                        <Form.Control type="email" id="email" onChange = {(e) => handleInputChange(e)} placeholder={currentUser.email} />
                                    </Form.Group>


                                        <Form.Group className="mb-3">
                                            <Form.Label>Password</Form.Label>
                                            <Form.Control type="password" id="password" onChange = {(e) => handleInputChange(e)} placeholder="New Password" />
                                            <Form.Text className="text-muted">
                                                Use a strong password.
                                            </Form.Text>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Label>Confirm Password</Form.Label>
                                            <Form.Control type="password" id="confirmPassword" onChange = {(e) => handleInputChange(e)} placeholder="Confirm New Password" />
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
                                        You have successfully changed details.
                                    </Alert>
                                    : null}
                                <Button variant="dark"
                                        onClick={handleSubmit}
                                        >
                                    Update Details
                                </Button>


                            </Form>
                        </Container>
                    </div>
                </div>
            }

        </div>
    )

}

export default ManageAccount;
