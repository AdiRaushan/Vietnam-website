import axios from 'axios';

// Replace with your actual local/hosted PHP project path
const api = axios.create({
  baseURL: 'http://localhost/your-folder-name', // e.g., 'http://localhost/travel_dashboard'
});

export const fetchPackages = () =>
  api.get('/get_package.php'); // Returns all packages

export const getPackage = (id) =>
  api.get(`/get_package.php?id=${id}`); // Returns single package

export const createPackage = (data) =>
  api.post('/create_package.php', data, {
    headers: { 'Content-Type': 'application/json' }
  });

export const updatePackage = (id, data) =>
  api.post(`/update_package.php?id=${id}`, data, {
    headers: { 'Content-Type': 'application/json' }
  });

export const deletePackage = (id) =>
  api.post(`/delete_package.php?id=${id}`);
