import { useEffect, useState } from "react";
import "./App.css";
import { IUser } from "./models/IUser";
import HttpClient from "./utilities/HttpClient";

function App() {
  const [user, setUser] = useState<IUser | null>(null);

  useEffect(() => {
    init();
  }, []);

  const init = async () => {
    const { data } = await HttpClient().get<{
      user: IUser | null;
    }>("/auth/init");
    setUser(data.user);
  };

  const login = async () => {
    const { data } = await HttpClient().post<{ content: { token: string } }>(
      "/auth/login",
      {
        email: "mikolaj73@gmail.com",
        password: "testtest",
      }
    );

    localStorage.setItem("token", data.content.token);
    await init();
  };

  const register = async () => {
    const { data } = await HttpClient().post("/auth/register", {
      email: "mikolaj73@gmail.com",
      password: "testtest",
    });
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p>User Email: {user?.email}</p>
      <button onClick={login}>Login</button>
      <button onClick={register}>Register</button>
    </div>
  );
}

export default App;
