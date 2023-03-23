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

function AdminUsers(props) {

    const [firstName, setFirstName] = useState(null);
    const [lastName, setLastName] = useState(null);
    const [email, setEmail] = useState(null);
    const [password,setPassword] = useState(null);
    const [confirmPassword,setConfirmPassword] = useState(null);
    const [jobRole,setJobRole] = useState(null);
    const [userType,setUserType] = useState(null);
    const [registered,setRegistered] = useState(false);
    const [errorMessage,setErrorMessage] = useState("");


    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])

    const [users, setUsers] = useState([]);

    const token = localStorage.getItem('token');

    useEffect( () => {
        fetch("http://localhost/api/users",
            {
                method: 'GET',
                headers: {"Authorization": "Bearer " + token}
            })
            .then(
                (response) => response.json()
            ).then(
            data => setUsers(data)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);



    const handleUserType = (user) => {
        if (user.rankID === 2) {
            return "Admin"
        } else if (user.rankID === 1) {
            return "Participant"
        }
    }

    const handleSubmit = () => {
        console.log(firstName,lastName,email,password,confirmPassword, jobRole, userType);
        if(password !== confirmPassword){
            setErrorMessage("Passwords do not match");
        }
        else {

            fetch('http://localhost/api/register.php',
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
                            window.location.reload(false);
                        }
                        else if (json.message === "Invalid Email Address!") {
                            setErrorMessage("Invalid Email address!");
                        }
                        else if (json.message === "SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: users.email") {
                            setErrorMessage("Email already exists!");
                        }
                        else if (json.message === "Your password must be at least 8 characters long!"){
                            setErrorMessage("Password must be at least 8 characters long!");
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

    const handleDeleteUser = (userID) => {
        const filteredUsers = users.filter((user) => user.userID !== userID);
        setUsers(filteredUsers);
        console.log(userID);

        fetch("http://localhost/api/users/" + userID,
            {
                method: 'DELETE',
                headers: {"Authorization": "Bearer " + token}
            })
            .then(
                (response) => response.json()
            ).then(
            data => setUsers(data)
        ).catch((err) => {
            console.log(err.message);
        });

    };


    return(
        <div>
            {props.authenticated &&
                <div>
                    <br/>
                    <h2>Add Users</h2>
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
                                        Account successfully created.
                                    </Alert>
                                    : null}
                                <div>
                                <Button variant="dark"
                                        onClick={handleSubmit}
                                        disabled={!firstName || !lastName || !jobRole || !email || !password || !confirmPassword || !userType}>
                                    Create Account
                                </Button>
                                    <br/>
                                </div>


                            </Form>
                        </Container>
                    </div>
                    <br/>
                    <h2>Manage users</h2>
                    <Table striped bordered hover variant="dark">
                        <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Job Role</th>
                            <th>User Type</th>
                            <th>Delete User</th>
                        </tr>
                        </thead>
                        <tbody>
                        {users.map((user) => (
                            <tr key={user.userID}>
                                <td>{user.firstName}</td>
                                <td>{user.lastName}</td>
                                <td>{user.email}</td>
                                <td>{user.jobRole}</td>
                                <td>{handleUserType(user)}</td>
                                <td>
                                    <Button variant="danger" onClick={() => handleDeleteUser(user.userID)}>
                                        Delete
                                    </Button>
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </Table>
                    <br/>
                </div>
            }

        </div>
    )

}

export default AdminUsers;
