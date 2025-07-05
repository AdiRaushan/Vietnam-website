import React, { useEffect, useState } from 'react';
import { fetchPackages, deletePackage } from '../api';
import { useNavigate } from 'react-router-dom';

const Dashboard = () => {
  const [packages, setPackages] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    loadPackages();
  }, []);

  const loadPackages = async () => {
    try {
      const res = await fetchPackages();
      setPackages(res.data);
    } catch (err) {
      console.error('Failed to fetch packages', err);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this package?")) return;
    try {
      await deletePackage(id);
      loadPackages(); // Refresh list
    } catch (err) {
      alert("Delete failed");
      console.error(err);
    }
  };

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Travel Packages</h1>
        <button
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          onClick={() => navigate('/add')}
        >
          + Add New Package
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {packages.map((pkg) => (
          <div key={pkg.id} className="border rounded-lg shadow p-4 bg-white">
            <img
              src={pkg.images?.[0] || 'https://via.placeholder.com/300'}
              alt={pkg.title}
              className="w-full h-48 object-cover rounded mb-4"
            />
            <h2 className="text-xl font-semibold">{pkg.title}</h2>
            <p className="text-gray-600">{pkg.city}</p>
            <p className="text-sm mt-1">Style: {pkg.style}</p>
            <p className="mt-1 text-green-600 font-medium">₹{pkg.price}</p>
            <p className="text-yellow-600 mt-1">⭐ {pkg.rating}</p>

            <div className="mt-4 flex gap-2">
              <button
                onClick={() => navigate(`/edit/${pkg.id}`)}
                className="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Edit
              </button>
              <button
                onClick={() => handleDelete(pkg.id)}
                className="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
              >
                Delete
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Dashboard;
