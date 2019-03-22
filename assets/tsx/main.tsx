import ReactDOM from 'react-dom';
import * as React from 'react';

const element = document.getElementById('root');
const id = element.getAttribute('data-id');

ReactDOM.render(<div id={id}/>, element);
