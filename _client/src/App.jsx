import {BrowserRouter} from "react-router-dom";
import './assets/libs/@mdi/font/css/materialdesignicons.min.css'
import 'react-notifications/lib/notifications.css';
import './assets/fonts/feather/feather.css'
import './assets/css/theme.min.css'
import './assets/css/custom.min.css'
import {RequestProvider} from "./contexts/RequestProvider.jsx";
import {AuthProvider} from "./contexts/AuthProvider.jsx";
import Routing from "./Routing.jsx";
import {DataProvider} from "./contexts/DataProvider.jsx";


function App() {

  return (
      <DataProvider>
        <RequestProvider>
          <AuthProvider>
            <BrowserRouter>
              <Routing/>
            </BrowserRouter>
          </AuthProvider>
        </RequestProvider>
      </DataProvider>
  )
}


export default App
