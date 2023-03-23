import './App.css';

import {Routes, Route} from 'react-router-dom';

import React, {useState, useEffect} from 'react';

import HomePage from './components/HomePage';
import Login from "./components/Login";
import Register from "./components/Register";
import IndividualStage from './components/IndividualStage.js';
import AdminItems from './components/AdminItems.js';
import SecInfoPage from "./components/SecInfo";
import Dashboard from "./components/Dashboard";
import AdminUsers from "./components/AdminUsers";
import GroupSelect from './components/GroupSelect';
import GroupStage from './components/GroupStage';
import ManageAccount from "./components/ManageAccount";

function App() {


  const [authenticated, setAuthenticated] = useState(false);
  const handleAuthenticated = (isAuthenticated) => {
    setAuthenticated(isAuthenticated)
  }

  return (
    <div className="App">
      <header className="App-header">
        
        <Routes>
          <Route path="/" element={<HomePage/>}/>
          <Route path="/login" element={<Login handleAuthenticated={setAuthenticated} authenticated={authenticated}/>}/>
          <Route path="/register" element={<Register handleAuthenticated={setAuthenticated}/>}/>
          {/* <Route path="*" element={<p>Not Found</p>}/> */}
          <Route path="/individualStage" element={<IndividualStage/>}/>
          <Route path="/startExperiment" element={<GroupSelect/>}/>
          <Route path="/AdminItems" element={<AdminItems/>}/>
          <Route path="/SecInfo" element={<SecInfoPage/>}/>
          <Route path="/dashboard" element={<Dashboard handleAuthenticated={setAuthenticated} authenticated={authenticated}/>}/>
          <Route path="/adminUsers" element={<AdminUsers handleAuthenticated={setAuthenticated} authenticated={authenticated}/>}/>
          <Route path="/ManageAccount" element={<ManageAccount handleAuthenticated={setAuthenticated} authenticated={authenticated}/>}/>
          <Route path="/groupStage" element={<GroupStage/>}/>
        </Routes>
      </header>
    </div>
  );
}

export default App;
