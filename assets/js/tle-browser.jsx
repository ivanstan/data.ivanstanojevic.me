import React from 'react';
import Select from 'react-select';
import ReactDOM from 'react-dom';

export default class TleBrowser extends React.Component {
  constructor () {
    super();

    this.state = {
      selected: null,
      options: []
    };
  }

  handleChange (selected) {
    this.setState({selected: selected});
    console.log(`Option selected:`, selected);
  }

  inputChange (input) {
    fetch(`${this.props.url}?search=${input}`)
      .then(response => response.json())
      .then((response) => {
        let options = response.member.map((item) => {
          item.label = item.name;
          item.value = item.satelliteId;

          return item;
        });

        this.setState({
          options: options
        });
      });
  }

  render () {
    var title = 'TLE Browser';
    if (this.state.selected !== null) {
      title = this.state.selected.name;
    }

    return (
      <div>
        <h1>{title}</h1>
        <Select value={this.state.selected}
          onChange={this.handleChange.bind(this)}
          onInputChange={this.inputChange.bind(this)}
          options={this.state.options}
          isSearchable={true}
          placeholder="Search satellites"
          autosize={false}/>
      </div>
    );
  }
}

let element = document.getElementById('tle-browser');
let url = element.getAttribute('data-url');

if (element) {
  ReactDOM.render(<TleBrowser url={url}/>, element);
}
