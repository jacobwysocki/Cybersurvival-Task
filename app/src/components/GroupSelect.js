import React from "react";
import { useState, useEffect } from "react";

function GroupSelect(){
    const [groups, setGroups] = useState([]);

    useEffect( () => {
        fetch("http://127.0.0.1:8080/api/groups", {
            headers: new Headers( { "Authorization": "Bearer " + localStorage.getItem('token')})
          })
        .then( 
            (response) => response.json() 
        ).then(
            json => console.log(json)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);

    return(<div>
            <h2>Select the group you want to join</h2>
            <ul>
               {groups.map(
                                (value) => 
                                <li>{value.groupName}</li>
                                )}
            </ul>
            <p>HERE</p>
    </div>);
}

export default GroupSelect;