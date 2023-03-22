import React, { useState, useEffect } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';

function AdminItems(props) {
  const [items, setItems] = useState([]);
  const [itemInput, setItemInput] = useState('');
  const [editingItem, setEditingItem] = useState(null);

  useEffect(() => {
    fetch("http://localhost/api/items")
      .then((response) => response.json())
      .then((data) => setItems(data))
      .catch((err) => {
        console.log(err.message);
      });
  }, []);

  function addItem () {
    if (itemInput && itemInput.trim() !== '') {
      if (editingItem == null) {
        const newItem = {
          itemID: items.length + 1,
          itemString: itemInput,
          sequenceNo: Math.random()
        };
        setItems([...items, newItem]);
      } else {
        const newItems = [...items];
        newItems[editingItem].itemString = itemInput;
        setItems(newItems);
        setEditingItem(null);
      }
      setItemInput('');
    }
  }


  function editItem(index) {
    const itemToEdit = items[index];
    setItemInput(itemToEdit.itemString);
    setEditingItem(index);
  }
  

  function deleteItem(index) {
    const newItems = [...items];
    newItems.splice(index, 1);
    setItems(newItems);
  }

  return (
    <div>
      <div>
        <h1>Manage items</h1>
        <br />
        <div className="formArea">
          <div className="form-container">
            <Form.Control
              type="text"
              placeholder="Enter item name"
              value={itemInput}
              onChange={(e) => setItemInput(e.target.value)}
            />

            <Button
              className="button"
              variant="dark"
              type="submit"
              onClick={addItem}
            >
              {editingItem == null ? 'Add Item' : 'Submit changes'}
            </Button>

            <br />
            <br />

            <ul>
              {items.map((item, index) => (
                <li key={index}>
                  {item.itemString}

                  <Button
                    variant="dark"
                    onClick={() => editItem(index)}>
                    Edit
                  </Button> 
                  <Button
                    variant="dark"
                    onClick={() => deleteItem(index)} >
                    Delete
                  </Button>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}

export default AdminItems;