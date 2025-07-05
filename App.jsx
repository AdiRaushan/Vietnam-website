import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

import Dashboard from './components/Dashboard';
import EditPackage from './components/EditPackage';

const App = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/add" element={<EditPackage />} />
        <Route path="/edit/:id" element={<EditPackage />} />
      </Routes>
    </Router>
  );
};

export default App;