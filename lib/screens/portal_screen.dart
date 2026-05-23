import 'package:flutter/material.dart';

class PortalScreen extends StatefulWidget {
  const PortalScreen({super.key});

  @override
  State<PortalScreen> createState() => _PortalScreenState();
}

class _PortalScreenState extends State<PortalScreen> {
	GlobalKey<FormState> formKey = GlobalKey<FormState>();

	@override
	Widget build(BuildContext context) {
		return Scaffold(
			body: Container(
				child: Center(
					child: Column(
						mainAxisAlignment: .center,
						spacing: 16,
						children: <Widget>[
							Text(
								'Logo Here'
							),
							Text(
								'Selamat Datang di Aplikasi HIMATIK',
							),
							Form(
								key: formKey,
								child: Container(
									alignment: .centerLeft,
									width: 320,
									child: Column(
										children: [
											Column(
												spacing: 8,
												children: [
													Text('Email'),
													TextFormField(
														decoration: InputDecoration(
															hintText: 'john.doe@stu.pnj.ac.id',
															border: OutlineInputBorder( 
																borderRadius: .all(Radius.circular(12)),
															),
														),
													),
												]
											),
											FilledButton.icon(
												onPressed: () { print('Login'); }, 
												icon: null,
												label: const Text('Login ke Aplikasi'),
											),
											FilledButton.icon(
												onPressed: () { print('Daftar'); }, 
												label: const Text('Daftar Menjadi Calon')
											),
										],
								)
								) 
							)
						],
					),
				),
			)
		);
	}
}
