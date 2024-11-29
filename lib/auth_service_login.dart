import 'dart:convert';
import 'package:http/http.dart' as http;

// Función para autenticar usuario
Future<Map<String, dynamic>> loginUser(String email, String password) async {
  final response = await http.post(
    Uri.parse('http://66.94.116.235/AppIntec1.0/login.php'), // Cambia esto a tu dirección de servidor
    body: {
      'email': email,
      'pass_user': password,
    },
  );

  if (response.statusCode == 200) {
    return json.decode(response.body);
  } else {
    throw Exception('Failed to login');
  }
}
