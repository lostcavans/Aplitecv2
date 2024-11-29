import 'package:flutter/material.dart';
import 'package:intec_appflutter/home_screen.dart';
import 'package:intec_appflutter/reg_user.dart';
import 'auth_service_login.dart';  // Asegúrate de que esta ruta sea correcta según tu estructura de proyecto

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  String errorMessage = '';
  bool _obscurePassword = true; // Estado para controlar la visibilidad de la contraseña

  void _login() async {
    String email = _emailController.text;
    String password = _passwordController.text;

    try {
      var response = await loginUser(email, password);
      if (response['status'] == 'success') {
        // Navegar a la pantalla principal si el login es exitoso
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => HomeScreen()),
        );
      } else {
        setState(() {
          errorMessage = response['message'];
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Error al conectar con el servidor.';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Eliminar o comentar la appBar para quitar la barra de título
      // appBar: AppBar(
      //   title: const Text('Login'),
      //   backgroundColor: Colors.blueAccent,  // Azul oscuro
      //   foregroundColor: Colors.white,
      //   elevation: 0,
      //   centerTitle: true,
      // ),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [
              Color(0xFF1E3A8A),  // Azul oscuro
              Color(0xFF60A5FA),  // Azul claro
            ],
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 32.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: <Widget>[
                // Contenedor circular blanco detrás del logo
                Container(
                  width: 150,  // Tamaño del contenedor
                  height: 150,  // Tamaño del contenedor
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,  // Forma circular
                    color: Colors.white,     // Fondo blanco
                  ),
                  child: Center(
                    child: Image.asset(
                      'logo.png', // Asegúrate de tener el logo en la carpeta assets
                      width: 150,
                      height: 150,
                    ),
                  ),
                ),
                const SizedBox(height: 20),
                Text(
                  'Aplicaciones Tecnológicas SRL', // Nombre de la empresa
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 20),
                // Campo de correo electrónico
                TextField(
                  controller: _emailController,
                  decoration: InputDecoration(
                    prefixIcon: const Icon(Icons.email, color: Colors.blue),
                    labelText: 'Email',
                    filled: true,
                    fillColor: Colors.white,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: const EdgeInsets.symmetric(vertical: 15.0),
                  ),
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 20),
                // Campo de contraseña
                TextField(
                  controller: _passwordController,
                  decoration: InputDecoration(
                    prefixIcon: const Icon(Icons.lock, color: Colors.blue),
                    labelText: 'Password',
                    filled: true,
                    fillColor: Colors.white,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                      borderSide: BorderSide.none,
                    ),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscurePassword ? Icons.visibility_off : Icons.visibility,
                        color: Colors.blue,
                      ),
                      onPressed: () {
                        setState(() {
                          _obscurePassword = !_obscurePassword; // Cambia la visibilidad de la contraseña
                        });
                      },
                    ),
                    contentPadding: const EdgeInsets.symmetric(vertical: 15.0),
                  ),
                  obscureText: _obscurePassword, // Controla si la contraseña es visible o no
                ),
                const SizedBox(height: 30),
                // Botón de login
                ElevatedButton(
                  onPressed: _login,
                  style: ElevatedButton.styleFrom(
                    foregroundColor: Colors.blueAccent, 
                    backgroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(30.0),
                    ),
                    padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                  ),
                  child: const Text(
                    'Login',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                ),
                const SizedBox(height: 15),
                // Botón para ir al registro
                ElevatedButton(
                  onPressed: () {
                    // Navegar a la pantalla de registro
                    Navigator.push(
  context,
  MaterialPageRoute(builder: (context) => RegisterUserScreen()),
);

                  },
                  style: ElevatedButton.styleFrom(
                    foregroundColor: Colors.blueAccent, 
                    backgroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(30.0),
                    ),
                    padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                  ),
                  child: const Text(
                    'Regístrate Gratis',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                ),
                if (errorMessage.isNotEmpty) ...[
                  const SizedBox(height: 20),
                  Text(
                    errorMessage,
                    style: const TextStyle(color: Colors.red, fontSize: 16),
                  ),
                ],
              ],
            ),
          ),
        ),
      ),
    );
  }
}
