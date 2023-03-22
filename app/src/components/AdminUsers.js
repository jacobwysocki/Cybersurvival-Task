import React, {useState, useEffect} from 'react';
import { Navigate } from "react-router-dom";
import Button from "react-bootstrap/Button";
import Table from 'react-bootstrap/Table';

function AdminUsers(props) {

    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])

    const [users, setUsers] = useState([]);

    useEffect( () => {
        fetch("http://localhost/api/users")
            .then(
                (response) => response.json()
            ).then(
            data => setUsers(data)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);


    const handleSignOut = () => {
        props.handleAuthenticated(false)
        localStorage.removeItem('token')
    }


    return(
        <div>
            {props.authenticated &&
                <div>
                    <p>Manage users</p>
                    <Button className="buttonSignOut"
                            variant="dark"
                            type="submit"
                            onClick={handleSignOut}>
                        Sign out
                    </Button>
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
                        {users.map((user) => (
                            <tr key={user.userID}>
                                <td>{user.firstName}</td>
                                <td>{user.lastName}</td>
                                <td>{user.email}</td>
                                <td>{user.jobRole}</td>
                            </tr>
                        ))}
                        </tbody>
                    </Table>
                </div>
            }

        </div>
    )

}

export default AdminUsers;
