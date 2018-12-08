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

  componentDidMount ()
  {
    let match = window.location.pathname.match(/\/view\/([0-9]+)/);
    if (match !== null && typeof match[1] !== 'undefined') {
      let satelliteId = match[1];

      this.inputChange(satelliteId, satelliteId);
    }
  }

  inputChange (input, selected = null)
  {
    fetch(`${this.props.url}?search=${input}`)
      .then(response => response.json())
      .then((response) => {
        let options = response.member.map((item) => {
          item.label = item.name;
          item.value = item.satelliteId;

          return item;
        });

      let newSelected = null;
      if (selected !== null) {
        newSelected = options.find((item) => {
          return parseInt(item.satelliteId) === parseInt(selected);
        }) || null;
      }

        this.setState({
          options: options
        });

      if (newSelected !== null) {
        this.setState({
          selected: newSelected
        });
      }
      });
  }

  handleChangeText(event) {
    this.setState({value: event.target.value});
  }

  render () {
    var title = 'TLE Browser';
    var preview = '';
    var subtitle = '';
    var api = '';
    if (this.state.selected !== null) {
      title = this.state.selected.name;
      subtitle = <h5 className="text-muted">Latest two line element for {title}</h5>;
      preview = <div className="tle-preview mt-5">
        <pre className="mb-0">{this.state.selected.line1}</pre>
        <pre className="mb-0">{this.state.selected.line2}</pre>
        {/*<span className="line1 line-number"></span>*/}
        {/*<span className="line2 line-number"></span>*/}
        {/*<span className="line1 catalog-number"></span>*/}
        {/*<span className="line2 catalog-number"></span>*/}
        {/*<span className="line1 checksum"></span>*/}
        {/*<span className="line2 checksum"></span>*/}
        {/*<span className="line1 classification"></span>*/}
        {/*<span className="line1 launch-year"></span>*/}
        {/*<span className="line1 launch-number"></span>*/}
        {/*<span className="line1 launch-piece"></span>*/}
        {/*<span className="line1 epoch"></span>*/}
        {/*<span className="line1 first-derivative"></span>*/}
        {/*<span className="line1 second-derivative"></span>*/}
        {/*<span className="line1 bstar"></span>*/}
        {/*<span className="line1 ephemeris-type"></span>*/}
        {/*<span className="line1 element-number"></span>*/}
        {/*<span className="line2 inclination"></span>*/}
        {/*<span className="line2 raan"></span>*/}
        {/*<span className="line2 mean-motion"></span>*/}
        {/*<span className="line2 mean-anomaly"></span>*/}
        {/*<span className="line2 eccentricity"></span>*/}
        {/*<span className="line2 argument-of-perigee"></span>*/}
        {/*<span className="line2 revolution"></span>*/}
      </div>;
//      api = <div>
//        <label>This data is available over API</label>
//        <input className="form-control" type="text" defaultValue={this.state.selected['@id']} onChange={this.handleChangeText}/>
//      </div>;
    }

    return (
      <div>
        <div className="mb-5">
        <h1>{title}</h1>
          {subtitle}
        </div>
        <div className="form-group mb-5">
        <label htmlFor="tle-select">Search Satellites</label>
        <Select value={this.state.selected}
          onChange={this.handleChange.bind(this)}
          onInputChange={this.inputChange.bind(this)}
          options={this.state.options}
          isSearchable={true}
          placeholder="Search satellites"
          id="tle-select"
          autosize={false}/>
        {preview}
        </div>
        {api}
      </div>
    );
  }
}

let element = document.getElementById('tle-browser');
if (element) {
  let url = element.getAttribute('data-url');
  ReactDOM.render(<TleBrowser url={url}/>, element);
}
