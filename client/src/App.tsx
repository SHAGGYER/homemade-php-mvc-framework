import { useEffect, useState } from "react";
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

  const logout = () => {
    localStorage.removeItem("token");
    setUser(null);
  };

  const register = async () => {
    const { data } = await HttpClient().post("/auth/register", {
      email: "mikolaj73@gmail.com",
      password: "testtest",
    });
  };

  const getUsers = async () => {
    const { data } = await HttpClient().get<{ users: IUser[] }>("/users");
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p>User Email: {user?.email}</p>
      <div className="flex gap-4 items-center">
        <button className="py-2 px-4 border border-gray-500" onClick={login}>
          Login
        </button>
        <button className="py-2 px-4 border border-gray-500" onClick={register}>
          Register
        </button>
        <button className="py-2 px-4 border border-gray-500" onClick={logout}>
          Logout
        </button>
        {user?.id && (
          <button
            className="py-2 px-4 border border-gray-500"
            onClick={getUsers}
          >
            Get Users
          </button>
        )}
      </div>
    </div>
  );
}

export default App;
