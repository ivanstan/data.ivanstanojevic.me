import * as React from 'react';
import {BootstrapThemeProvider} from './BootstrapThemeProvider';
import {Route, Switch} from 'react-router';
import {HashRouter} from 'react-router-dom';

interface MainProps {

}

interface MainState {

}

export class Main extends React.Component<MainProps, MainState> {

    public state: Readonly<MainState>;

    constructor(props) {
        super(props);
    }

    componentDidMount(): void {

    }

    componentWillReceiveProps(nextProps: Readonly<MainProps>, nextContext: any): void {

    }

    render() {
        return <HashRouter>
            <BootstrapThemeProvider>
                <Switch>
                    <Route exact path='/lesson/:id' component={props => <div/>}/>
                </Switch>
            </BootstrapThemeProvider>
        </HashRouter>
    }
}
