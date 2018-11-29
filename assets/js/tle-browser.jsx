import React from 'react';
import Select from 'react-select';
import ReactDOM from 'react-dom';

const options = [
  {value: 'chocolate', label: 'Chocolate'},
  {value: 'strawberry', label: 'Strawberry'},
  {value: 'vanilla', label: 'Vanilla'},
];

class TleBrowser extends React.Component
{
  constructor() {
    super();
    this.state = {
      selectedOption: null
    };
  }

  handleChange(selectedOption) {
    this.setState({selectedOption});
    console.log(`Option selected:`, selectedOption);
  }

  render()
  {
    const {selectedOption} = this.state;

    return (
        <Select width="100%"
            value={selectedOption}
            onChange={this.handleChange.bind(this)}
            options={options}
        />
    );
  }
}

let element = document.getElementById('tle-browser');
if (element) {
  ReactDOM.render(<TleBrowser/>, element);
}
