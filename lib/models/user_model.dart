class UserModel {
  final int id;
  final String name;
  final String email;
  final String role;
  final bool emailVerified;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    required this.emailVerified,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      role: json['role'] as String? ?? '',
      emailVerified: json['email_verified'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'email_verified': emailVerified,
    };
  }
}
