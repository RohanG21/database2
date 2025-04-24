package com.database.db2;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class StudentMain extends AppCompatActivity {
    Button enrollBtn;
    Button viewCoursesBtn;
    Button rateProfessorBtn;
    Button returnLogin;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.studentmain);

        TextView welcomeName = (TextView) findViewById(R.id.welcomeName);
        Intent intent = getIntent();
        final String email = intent.getStringExtra("email");
        final String password = intent.getStringExtra("password");
        final String name = intent.getStringExtra("name");
        final String sid = intent.getStringExtra("sid");
        String welcome = "Welcome " + name;
        welcomeName.setText(welcome);

        enrollBtn = (Button) findViewById(R.id.enroll_btn);
        viewCoursesBtn = (Button) findViewById(R.id.view_courses_btn);
        rateProfessorBtn = (Button) findViewById(R.id.rate_professor_btn);
        returnLogin = (Button) findViewById(R.id.login_btn);
        enrollBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent enrollIntent = new Intent(StudentMain.this, Enroll.class);
                enrollIntent.putExtra("email", email);
                enrollIntent.putExtra("password", password);
                enrollIntent.putExtra("sid",sid);
                StudentMain.this.startActivity(enrollIntent);
            }
        });
        returnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent loginIntent = new Intent(StudentMain.this, Login.class);
                StudentMain.this.startActivity(loginIntent);
            }
        });
    }
}