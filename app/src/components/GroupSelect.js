import React from "react";
import { useState, useEffect } from "react";
import {Navigate} from "react-router-dom";

function GroupSelect(){
    const [groups, setGroups] = useState([]);
    const [valid, setValid] = useState(false);

    const handleJoinGroup = (e) =>{
        fetch("http://127.0.0.1:8080/api/groups/" + e.target.value,{
            method: "POST",
            headers: {
                "Authorization" : "Bearer " + localStorage.getItem('token')
            }
        })
        .then(
            response =>{
                if(response.status === 200){
                    localStorage.setItem('groupID', e.target.value);
                    setValid(true);
                }
            }
        )
    }

    useEffect( () => {
        fetch("http://127.0.0.1:8080/api/groups", {
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('token')
               }
          })
        .then( 
            (response) => response.json() 
        ).then(
            json => setGroups(json)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);

    return(<div>
            <h2>Select the group you want to join</h2>
            <ul>
               {groups.map(
                                (value) => 
                                <li key={value.groupID}>{value.groupName} <button value={value.groupID} onClick={handleJoinGroup}>Join</button></li>
                                )}
            </ul>
            {
                valid && 
                <Navigate to="/individualStage" />
            }
    </div>);
}

export default GroupSelect;