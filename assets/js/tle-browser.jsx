import React from 'react';
import Select from 'react-select';
import ReactDOM from 'react-dom';

export default class TleBrowser extends React.Component
{
  constructor()
  {
    super();

    this.state = {
      selected: null,
      options: []
    };
  }

  handleChange(selectedOption)
  {
    this.setState({selectedOption});
    console.log(`Option selected:`, selectedOption);
  }

  inputChange(input)
  {
    fetch(`${this.props.url}?search=${input}`)
    .then(response => response.json())
    .then((response) => {

      let options = response.member.map((item) => {
        item.label = item.name;
        item.value = item.catalogId;

        return item;
      });

      this.setState({
        options: options,
      });
    });
  }

  render()
  {
    return (
        <div>
        <Select value={this.state.selected}
                onChange={this.handleChange.bind(this)}
                onInputChange={this.inputChange.bind(this)}
                options={this.state.options}
                isSearchable={true}
                placeholder=""
                autosize={false}
                style={{width: '100%'}}
        />
        </div>
    );
  }
}

let element = document.getElementById('tle-browser');
let url = element.getAttribute('data-url');

if (element) {
  ReactDOM.render(<TleBrowser url={url}/>, element);
}
