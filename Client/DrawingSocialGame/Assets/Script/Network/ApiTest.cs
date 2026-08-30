using System.Collections;
using UnityEngine;
using UnityEngine.Networking;

public class  ApiTest : MonoBehaviour
{
    private const string ApiUrl = "http://127.0.0.1:8000/api/test";

    private void Start()
    {
        StartCoroutine(TestApi());
    }

    private IEnumerator TestApi()
    {
        using UnityWebRequest request = UnityWebRequest.Get(ApiUrl);

        yield return request.SendWebRequest();

        if(request.result == UnityWebRequest.Result.Success)
        {
            Debug.Log($"API Response: {request.downloadHandler.text}");
        }
        else
        {
            Debug.LogError($"API Request Error: {request.error}");
        }
    }
}