import React, { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import axios from 'axios';

const defaultData = {
  title: '',
  city: '',
  style: '',
  days: 3,
  price: 0,
  rating: 0,
  images: [''],
  highlights: [''],
  inclusions: [{ label: '', icon: '' }],
  exclusions: [''],
  activities: [{ name: '', image: '' }],
  where_to_visit: [{ place: '', image: '', description: '' }],
  itinerary: [{ day: '', title: '', description: '' }],
};

const EditPackage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEdit = Boolean(id);
  const [pkg, setPkg] = useState(defaultData);

  useEffect(() => {
    if (isEdit) {
      axios.get(`/get_package.php?id=${id}`)
        .then(res => setPkg(res.data))
        .catch(err => console.error('Failed to load package', err));
    }
  }, [id]);

  const handleChange = (field, value) => {
    setPkg(prev => ({ ...prev, [field]: value }));
  };

  const handleArrayChange = (field, index, value) => {
    const updated = [...pkg[field]];
    updated[index] = value;
    setPkg(prev => ({ ...prev, [field]: updated }));
  };

  const handleNestedChange = (field, index, key, value) => {
    const updated = [...pkg[field]];
    updated[index][key] = value;
    setPkg(prev => ({ ...prev, [field]: updated }));
  };

  const handleAddField = (field, defaultItem) => {
    setPkg(prev => ({ ...prev, [field]: [...prev[field], defaultItem] }));
  };

  const handleRemoveField = (field, index) => {
    const updated = [...pkg[field]];
    updated.splice(index, 1);
    setPkg(prev => ({ ...prev, [field]: updated }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();

    Object.keys(pkg).forEach(key => {
      if (Array.isArray(pkg[key])) {
        pkg[key].forEach((item, index) => {
          if (typeof item === 'object') {
            Object.keys(item).forEach(subKey => {
              formData.append(`${key}[${index}][${subKey}]`, item[subKey]);
            });
          } else {
            formData.append(`${key}[${index}]`, item);
          }
        });
      } else {
        formData.append(key, pkg[key]);
      }
    });

    try {
      if (isEdit) {
        await axios.post(`/update_package.php?id=${id}`, formData);
        alert('Package updated!');
      } else {
        await axios.post('/create_package.php', formData);
        alert('Package created!');
      }
      navigate('/');
    } catch (err) {
      console.error(err);
      alert('Error saving package');
    }
  };

  return (
    <div className="p-6 max-w-5xl mx-auto">
      <h1 className="text-2xl font-bold mb-4">{isEdit ? 'Edit' : 'Add'} Travel Package</h1>
      <form onSubmit={handleSubmit} className="space-y-6">
        <input className="w-full p-2 border rounded" placeholder="Title" value={pkg.title} onChange={e => handleChange('title', e.target.value)} />
        <input className="w-full p-2 border rounded" placeholder="City" value={pkg.city} onChange={e => handleChange('city', e.target.value)} />
        <input className="w-full p-2 border rounded" placeholder="Style" value={pkg.style} onChange={e => handleChange('style', e.target.value)} />
        <input className="w-full p-2 border rounded" type="number" placeholder="Days" value={pkg.days} onChange={e => handleChange('days', Number(e.target.value))} />
        <input className="w-full p-2 border rounded" type="number" placeholder="Price" value={pkg.price} onChange={e => handleChange('price', Number(e.target.value))} />
        <input className="w-full p-2 border rounded" type="number" step="0.1" placeholder="Rating" value={pkg.rating} onChange={e => handleChange('rating', Number(e.target.value))} />
        <button type="submit" className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          {isEdit ? 'Update' : 'Create'} Package
        </button>
      </form>
    </div>
  );
};

export default EditPackage;
