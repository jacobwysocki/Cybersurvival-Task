import '../App.css';
import React from 'react';
import {Link} from "react-router-dom";
import Button from 'react-bootstrap/Button';
/**
 * HomePage.
 * 
 * 
 */

function HomePage() {


    return (
        <div>
            <h1>Homepage</h1>
        <Button as={Link} to="/login" className="button"
          variant="dark"
          type="submit">
          Log in
        </Button>
        
        <Button as={Link} to="/register" className="button"
          variant="dark"
          type="submit">
          Register
        </Button>
            <div  className="d-flex flex-column justify-content-between min-vh-100">
                <Link to="/secinfo">
                    <Button className="button mt-2" variant="secondary">
                        Security Information
                    </Button>
                </Link>
            </div>
        </div>
    )
}

export default HomePage;