import SignIn from './SignIn';
import SignUp from './SignUp';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import Navbar from '../components/Navbar';
import Home from './Home';

function App() {
  return (
    <div className="App">
      <Navbar />
      <Switch>
          <Route exact path="/Sign-in" component={SignIn} />
          <Route exact path="/sign-up" component={SignUp} />
          <Route exact path="/" component={Home} />
      </Switch>
    </div>
  );
}

export default App;
