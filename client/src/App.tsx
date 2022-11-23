import { useEffect, useState } from "react";
import "./App.css";
import HttpClient from "./utilities/HttpClient";

function App() {
  useEffect(() => {
    init();
  }, []);

  const init = async () => {
    const { data } = await HttpClient().get("/auth/init");
    console.log(data);
  };

  return (
    <div className="App">
      <header className="App-header">
        <p>
          Edit <code>src/App.tsx</code> and save to reload.
        </p>
      </header>
    </div>
  );
}

export default App;
