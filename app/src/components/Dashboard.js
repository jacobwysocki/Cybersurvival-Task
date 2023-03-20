import Login from "./Login";
import React, {useState, useEffect} from 'react';
import { Navigate } from "react-router-dom";
import Button from "react-bootstrap/Button";


function Dashboard(props) {

    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])


    const handleSignOut = () => {
        props.handleAuthenticated(false)
        localStorage.removeItem('token')
    }

    const handleClick = (path) => {
        return <Navigate replace to={path} />;
    }


    return(
    <div>
        {props.authenticated &&
            <div>
            <p>Welcome to your Dashboard</p>
                <Button className="buttonSignOut"
                        variant="dark"
                        type="submit"
                        onClick={handleSignOut}>
                    Sign out
                </Button>
                <Button variant="dark"
                        type="submit"
                        onClick={() => handleClick('/AdminUsers')}>
                    Manage Users
                </Button>
        </div>
        }
        {!props.authenticated &&
            <Navigate replace to="/login"/>
        }
    </div>
)

}

export default Dashboard;
