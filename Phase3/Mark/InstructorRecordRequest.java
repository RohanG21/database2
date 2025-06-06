package com.database.db2;

import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

public class InstructorRecordRequest extends StringRequest {
    private Map<String, String> args;
    private static Response.ErrorListener err = new Response.ErrorListener(){
        @Override
        public void onErrorResponse(VolleyError error){
            Log.d("InstructorRecordRequest","Error listener response: " + error.getMessage());
        }
    };
    public InstructorRecordRequest(String instructor_id, String url, Response.Listener<String> listener){
        super(Method.POST, url, listener, err);
        args = new HashMap<String, String>();
        args.put("inid", instructor_id);
    }
    @Override
    protected Map<String, String> getParams() throws AuthFailureError {
        return args;
    }
}
