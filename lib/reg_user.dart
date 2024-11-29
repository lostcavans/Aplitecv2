import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

// Función para registrar usuarios
Future<void> registerUser({
  required String nombres,
  required String apelMat,
  required String apelPat,
  required String cel,
  required String fecNac,
  required String ci,
  required String pais,
  required String departamento,
  required String provincia,
  required String ciudad,
  required String zona,
  required String comp,
  required String passUser,
  required String email,
  required String inst,
}) async {
  final String url = 'http://66.94.116.235/InTec3.0/register_user.php';

  final Map<String, String> body = {
    'nombres': nombres,
    'apel_mat': apelMat,
    'apel_pat': apelPat,
    'cel': cel,
    'fec_nac': fecNac,
    'CI': ci,
    'pais': pais,
    'departamento': departamento,
    'provincia': provincia,
    'ciud': ciudad,
    'zona': zona,
    'comp': comp,
    'id_cargo': '2', // Cargo fijo como cliente
    'pass_user': passUser,
    'email': email,
    'inst': inst,
    'stat': '1', // Estado fijo como activo
  };

  try {
    final response = await http.post(
      Uri.parse(url),
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: body,
    );

    if (response.statusCode == 200) {
      final responseData = jsonDecode(response.body);
      if (responseData['success']) {
        print('Usuario registrado exitosamente.');
      } else {
        print('Error al registrar usuario: ${responseData['message']}');
      }
    } else {
      print('Error del servidor: ${response.statusCode}');
    }
  } catch (e) {
    print('Error de conexión: $e');
  }
}

// Pantalla de registro de usuario
class RegisterUserScreen extends StatefulWidget {
  @override
  _RegisterUserScreenState createState() => _RegisterUserScreenState();
}

class _RegisterUserScreenState extends State<RegisterUserScreen> {
  // Controladores para los campos del formulario
  final TextEditingController nombresController = TextEditingController();
  final TextEditingController apelMatController = TextEditingController();
  final TextEditingController apelPatController = TextEditingController();
  final TextEditingController celController = TextEditingController();
  final TextEditingController fecNacController = TextEditingController();
  final TextEditingController ciController = TextEditingController();
  final TextEditingController paisController = TextEditingController();
  final TextEditingController departamentoController = TextEditingController();
  final TextEditingController provinciaController = TextEditingController();
  final TextEditingController ciudadController = TextEditingController();
  final TextEditingController zonaController = TextEditingController();
  final TextEditingController compController = TextEditingController();
  final TextEditingController passUserController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController instController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Registrar Usuario'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: nombresController,
              decoration: InputDecoration(labelText: 'Nombres'),
            ),
            TextField(
              controller: apelMatController,
              decoration: InputDecoration(labelText: 'Apellido Materno'),
            ),
            TextField(
              controller: apelPatController,
              decoration: InputDecoration(labelText: 'Apellido Paterno'),
            ),
            TextField(
              controller: celController,
              decoration: InputDecoration(labelText: 'Celular'),
              keyboardType: TextInputType.phone,
            ),
            TextField(
              controller: fecNacController,
              decoration: InputDecoration(labelText: 'Fecha de Nacimiento'),
              keyboardType: TextInputType.datetime,
            ),
            TextField(
              controller: ciController,
              decoration: InputDecoration(labelText: 'CI'),
            ),
            TextField(
              controller: paisController,
              decoration: InputDecoration(labelText: 'País'),
            ),
            TextField(
              controller: departamentoController,
              decoration: InputDecoration(labelText: 'Departamento'),
            ),
            TextField(
              controller: provinciaController,
              decoration: InputDecoration(labelText: 'Provincia'),
            ),
            TextField(
              controller: ciudadController,
              decoration: InputDecoration(labelText: 'Ciudad'),
            ),
            TextField(
              controller: zonaController,
              decoration: InputDecoration(labelText: 'Zona'),
            ),
            TextField(
              controller: compController,
              decoration: InputDecoration(labelText: 'Complemento'),
            ),
            TextField(
              controller: passUserController,
              decoration: InputDecoration(labelText: 'Contraseña'),
              obscureText: true,
            ),
            TextField(
              controller: emailController,
              decoration: InputDecoration(labelText: 'Email'),
              keyboardType: TextInputType.emailAddress,
            ),
            TextField(
              controller: instController,
              decoration: InputDecoration(labelText: 'Institución'),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                // Llama a la función para registrar el usuario
                registerUser(
                  nombres: nombresController.text,
                  apelMat: apelMatController.text,
                  apelPat: apelPatController.text,
                  cel: celController.text,
                  fecNac: fecNacController.text,
                  ci: ciController.text,
                  pais: paisController.text,
                  departamento: departamentoController.text,
                  provincia: provinciaController.text,
                  ciudad: ciudadController.text,
                  zona: zonaController.text,
                  comp: compController.text,
                  passUser: passUserController.text,
                  email: emailController.text,
                  inst: instController.text,
                );
              },
              child: Text('Registrar'),
            ),
          ],
        ),
      ),
    );
  }
}
